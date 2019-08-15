import requests
import urllib3

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

r = requests.post(  "https://localhost/phplogin/api.php", 
                    data={
                        "action": "Login",
                        "name": "admin",
                        "password": "admin"
                    },
                    verify=False)

r = requests.post(  "https://localhost/phplogin/api.php", 
                    data={
                        "action": "Login",
                        "name": "admin",
                        "password": "not admin password"
                    },
                    verify=False)
print(r)
print(r.text)