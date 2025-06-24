import json
import csv

with open("public/stasiun_features.json", encoding="utf-8") as f:
    features = json.load(f)

with open("public/stasiun.csv", "w", newline='', encoding="utf-8") as csvfile:
    fieldnames = ["namobj", "kodkod", "kabkot", "provinsi", "kelas", "status_ope", "lon", "lat"]
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames)
    writer.writeheader()
    for feat in features:
        prop = feat["properties"]
        lon, lat = feat["geometry"]["coordinates"][:2]
        writer.writerow({
            "namobj": prop.get("namobj"),
            "kodkod": prop.get("kodkod"),
            "kabkot": prop.get("kabkot"),
            "provinsi": prop.get("provinsi"),
            "kelas": prop.get("kelas"),
            "status_ope": prop.get("status_ope"),
            "lon": lon,
            "lat": lat
        })
print("Berhasil membuat stasiun.csv")
