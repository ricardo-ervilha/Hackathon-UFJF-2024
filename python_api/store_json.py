import json

def save_json_aux(data, filename):
    path = f'./storage/{filename}.json'
    
    with open(path, "w") as arquivo:
        json.dump(data, arquivo, indent=4, ensure_ascii=False)