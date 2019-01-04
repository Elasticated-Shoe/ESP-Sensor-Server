import requests
import urllib3
import configparser

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

url = "https://localhost/phplogin/sensorAPI"
config = configparser.ConfigParser() # Initialise the config parser
config.read("../framework/config.ini") # Read the config file
config = config["settings"]
password = config["applicationPassword"].replace('"', "")

r = requests.post(url, 
                    data={'password': "b6b6yv5", 'user': 'jacob'}, 
                    verify=False) # 404 as no ssl
print(r)
print(r.text)
print("Retrying..")
r = requests.post(url, 
                    data={'password': "bytbs", 'user': 'bob'}, 
                    verify=False) # 403 as user not found
print(r)
print(r.text)
print("Retrying..")
r = requests.post(url, 
                    data={'password': 'h6h45f43f', 'user': 'jacob'}, 
                    verify=False) # 403 as password incorrect
print(r)
print(r.text)
print("Retrying..")
r = requests.post(url, 
                    data={'password': password, 'user': 'jacob'}, 
                    verify=False) # 200
print(r)
print(r.text)
print("Trying Incorrect Password Case")
for i in range(7):
    print("LOOP START")
    r = requests.post(url, 
                    data={'password': 'ff43j6j', 'user': 'jacob'}, 
                    verify=False) # 403 as password incorrect
    print(r)
    print(r.text)
    print("LOOP END")
