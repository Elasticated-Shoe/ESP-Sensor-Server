import requests
import urllib3

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

r = requests.post(  "https://localhost/phplogin/api.php", 
                    data={
                        "action": "AddPermission",
                        "name": "admin", 
                        "readArchive": 1, 
                        "addPermission": 1,
                        "createUser": 1,
                        "loginUser": 1
                    },
                    verify=False)

print(r)
print(r.text)

r = requests.post(  "https://localhost/phplogin/api.php?action=CreateUser", 
                    data={
                        "action": "CreateUser",
                        "name": "admin", 
                        "password": "admin", 
                        "email": "jmetcalfe24@gmail.com"
                    },
                    verify=False)

print(r)
print(r.text)