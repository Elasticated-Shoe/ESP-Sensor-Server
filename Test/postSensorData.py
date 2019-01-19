import requests
import urllib3
import time
from random import randint
import configparser
import json

urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

config = configparser.ConfigParser() # Initialise the config parser
config.read("public_html/protected/config.ini") # Read the config file
config = config["settings"]
password = config["applicationPassword"].replace('"', "")

def calcVal(initial, name, current = None):
    global lastValues
    if initial == None:
        return None
    if current == None:
        current = initial
    while True:
        increment = randint(-1, 1)
        if current + increment < 10 or current + increment > 34:
            continue
        break
    current = current + randint(-1, 1)
    lastValues[name] = current
    return current

lastValues = {}
lastValues["Pi"] = None
lastValues["Omicron"] = None
lastValues["Alpha"] = None
lastValues["Beta"] = None
lastValues["Gamma"] = None
lastValues["Delta"] = None
lastValues["Epsilon"] = None
lastValues["Zeta"] = None
lastValues["Eta"] = None
lastValues["Theta"] = None
lastValues["Iota"] = None
lastValues["Kappa"] = None
lastValues["Lambda"] = None
test = None

for i in range(60):
    if i == 34:
        test = 25
    data = {
        "Alpha": {"value": calcVal(26, "Pi", lastValues["Alpha"]), "time": int(time.time()) },
        "Beta": {"value": calcVal(24, "Pi", lastValues["Beta"]), "time": int(time.time()) },
        "Gamma": {"value": calcVal(23, "Pi", lastValues["Gamma"]), "time": int(time.time()) },
        "Delta": {"value": calcVal(23, "Pi", lastValues["Delta"]), "time": int(time.time()) },
        "Zeta": {"value": calcVal(22, "Pi", lastValues["Zeta"]), "time": int(time.time()) },
        "Eta": {"value": calcVal(18, "Pi", lastValues["Eta"]), "time": int(time.time()) },
        "Theta": {"value": calcVal(30, "Pi", lastValues["Theta"]), "time": int(time.time()) },
        "Iota": {"value": calcVal(19, "Pi", lastValues["Iota"]), "time": int(time.time()) },
        "Kappa": {"value": calcVal(23, "Pi", lastValues["Kappa"]), "time": int(time.time()) },
        "Omicron": {"value": calcVal(29, "Omicron", lastValues["Omicron"]), "time": int(time.time()) },
        "Epsilon": {"value": calcVal(19, "Epsilon", lastValues["Epsilon"]), "time": int(time.time()) },
        "Lambda": {"value": calcVal(test, "Lambda", lastValues["Lambda"]), "time": int(time.time()) },
    }
    #print(data)
    r = requests.post("https://localhost/phplogin/sensorAPI", 
                        data={'password': password, 'user': 'jacob', "data": json.dumps(data)},
                        verify=False) # 200
    print(r.text)
    time.sleep(5)
