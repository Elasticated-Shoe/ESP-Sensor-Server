<?php
namespace App\Http\Requests;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\sensorMetaData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

abstract class FormRequest extends Request implements ValidatesWhenResolved
{
    use ValidatesWhenResolvedTrait;

    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container = null;
    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'default';
    /**
     * The validator instance.
     *
     * @var \Illuminate\Contracts\Validation\Validator
     */
    protected $validator = null;
    /**
     * Get the validator instance for the request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance(): Validator
    {
        if ($this->validator) {
            return $this->validator;
        }

        $factory = $this->container->make(ValidationFactory::class);
        if (method_exists($this, 'validator')) {
            $validator = $this->container->call([$this, 'validator'], compact('factory'));
        } else {
            $validator = $this->createDefaultValidator($factory);
        }

        if (method_exists($this, 'withValidator')) {
            $this->withValidator($validator);
        }

        $this->validator = $validator;

        return $validator;
    }
    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated(): array
    {
        return $this->validator->validated();
    }
    /**
     * Create the default validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Factory  $factory
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function createDefaultValidator(ValidationFactory $factory): Validator
    {
        return $factory->make(
            $this->validationData(), $this->container->call([$this, 'rules']),
            $this->messages(), $this->attributes()
        );
    }
    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData(): array
    {
        return $this->all();
    }
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, $this->formatErrors($validator));
    }
    /**
     * Format the errors from the given Validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return \Illuminate\Http\JsonResponse
     */
    protected function formatErrors(Validator $validator): JsonResponse
    {
        return new JsonResponse($validator->getMessageBag()->toArray(), 422);
    }
    protected function AuthorizeIfOwned($requestedSensorArray) {
        $token = Request::bearerToken();
		$credentials = $token ? JWT::decode($token, env('JWT_SECRET'), ['HS256']) : false;

		if(!$credentials) {
            // check if requesting for public sensors
            $allPublic = sensorMetaData::whereIn("sensorId", $requestedSensorArray)
                                        ->where('sensorPublic', true)
                                        ->get()->toArray();
                     
            $allPublicIdArray = array_column($allPublic, 'sensorId');
            $nonPublicSensors = array_diff($requestedSensorArray, $allPublicIdArray);
            if($nonPublicSensors) {
                throw new AuthorizationException(implode(", ", $nonPublicSensors) . " Not Public Sensors");
            }
            return true;
		}

		$ownedSensors = sensorMetadata::where("sensorOwner", $credentials->sub)->get()->toArray();
		$ownedSensorIdArray = array_column($ownedSensors, 'sensorId');
		
		$unownedSensors = array_diff($requestedSensorArray, $ownedSensorIdArray);
		
		if($unownedSensors) {
			throw new AuthorizationException(implode(", ", $unownedSensors) . " Not Owned By You");
        }
        
        return true;
    }
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [];
    }
    /**
     * Set the container implementation.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return $this
     */
    public function setContainer(Container $container): FormRequest
    {
        $this->container = $container;
        
        return $this;
    }
}