import os

def removeQuotesFromValue(value):
  value = value.replace("'", '"')
  return value

def splitLineIntoParts(line):
  line = line.lstrip()
  line = line.rstrip()
  line = removeQuotesFromValue(line)
  line = line.split("=", 1)
  return line

def setConfigVar(key, value):
  os.system(f"heroku config:set {key}={value}")

with open(".env") as env:
  for line in env:
    key_value_pair = splitLineIntoParts(line)
    if (len(key_value_pair) > 1):
      key = key_value_pair[0]
      value = key_value_pair[1]
      print(f"*** Setting {key} = {value}")
      setConfigVar(key, value)