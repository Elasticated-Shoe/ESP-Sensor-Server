import requests
import urllib3

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

r = requests.post("http://localhost/phplogin/index.php", 
                    data={'password': '2Jm466!6', 'user': 'jacob'}, 
                    verify=False) # 404 as no ssl
print(r)
print(r.text)
print("Retrying..")
r = requests.post("https://localhost/phplogin/index.php", 
                    data={'password': '2Jm466!6', 'user': 'bob'}, 
                    verify=False) # 403 as user not found
print(r)
print(r.text)
print("Retrying..")
r = requests.post("https://localhost/phplogin/index.php", 
                    data={'password': 'nvlalskn38', 'user': 'jacob'}, 
                    verify=False) # 403 as password incorrect
print(r)
print(r.text)
print("Retrying..")
r = requests.post("https://localhost/phplogin/index.php", 
                    data={'password': '2Jm466!6', 'user': 'jacob'}, 
                    verify=False) # 200
print(r)
print(r.text)
print("Complete")
