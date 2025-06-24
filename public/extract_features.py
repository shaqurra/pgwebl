import json

with open("public/Titik_Stasiun_Jabodetabek.json", encoding="utf-8") as f:
    data = json.load(f)

with open("public/stasiun_features.json", "w", encoding="utf-8") as f:
    json.dump(data["features"], f, ensure_ascii=False, indent=2)

print("Berhasil mengekstrak features ke stasiun_features.json")
