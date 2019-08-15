import requests
import urllib3

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

r = requests.get("https://localhost/phplogin/api.php?action=ArchivedReadings&start=1565805920&end=1565805938&sensors[]=Alpha&sensors[]=Zeta&sensors[]=Gamma", verify=False)

print(r)
print(r.text)

s = requests.session()
a = s.post(  "https://localhost/phplogin/api.php", 
                    data={
                        "action": "Login",
                        "name": "admin",
                        "password": "admin"
                    },
                    verify=False)

print(a)
print(a.text)

a = s.get("https://localhost/phplogin/api.php?action=ArchivedReadings&start=1565805920&end=1565805938&sensors[]=Alpha&sensors[]=Zeta&sensors[]=Gamma")

print(a)
print(a.text)