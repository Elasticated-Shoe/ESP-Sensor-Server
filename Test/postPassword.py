import requests
import urllib3
import configparser

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

url = "https://localhost/phplogin/sensorAPI"
httpurl = "http://localhost/phplogin/sensorAPI"
config = configparser.ConfigParser() # Initialise the config parser
config.read("public_html/protected/config.ini") # Read the config file
config = config["settings"]
password = config["applicationPassword"].replace('"', "")

print("Trying HTTP Case")
r = requests.post(httpurl, 
                    data={'password': "b6b6yv5", 'user': 'jacob'}, 
                    verify=False) # 404 as no ssl
print(r)
print(r.text)
print("\nTrying Invalid User Case")
r = requests.post(url, 
                    data={'password': "bytbs", 'user': 'bob'}, 
                    verify=False) # 403 as user not found
print(r)
print(r.text)
print("\nTrying Incorrect Password Case")
r = requests.post(url, 
                    data={'password': 'h6h45f43f', 'user': 'jacob'}, 
                    verify=False) # 403 as password incorrect
print(r)
print(r.text)
print("\nTrying Correct Password Case And Checking Session Maintained Between Requests")
session = requests.Session()
r = session.post(url, 
                    data={'password': password, 'user': 'jacob'}, 
                    verify=False) # 400 as correct login but no data
print(r)
print(r.text)
print("\nLOOP START")
r = session.post(url, 
                data={'test': "test"}, 
                verify=False) # 400 as logged in session but no data 
print(r)
print(r.text)
print("LOOP END")
print("\nTrying Incorrect Password Lockout Case\n")
for i in range(7):
    print("\nLOOP START")
    r = requests.post(url, 
                    data={'password': 'ff43j6j', 'user': 'jacob'}, 
                    verify=False) # 403 as password incorrect
    print(r)
    print(r.text)
    print("LOOP END")
