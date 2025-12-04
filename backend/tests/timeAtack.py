import time, requests, statistics

URL = "http://localhost/LOGIN-PHP/backend/public/index.php/login"
tests = ["Sege", "Sege2060", "Sege20602", "sege206020", "Sege20602"]
results = {}

print("\n--- INICIANDO PRUEBAS DE TIMING ATTACK ---\n")

for t in tests:
    print(f"Probando input: '{t}'")
    for attempt in range(1, 10):  # 50 intentos por input (ajústalo)
        start = time.time()
        response = requests.post(URL, data={"Pass": t,"User":"1623jsser@gmail.com"})
        elapsed = time.time() - start
        # Imprimir en consola
        print(f"  intento {attempt:03} | tiempo: {elapsed:.6f}s | status: {response.status_code} | resp: {response.text[:30]}")

    print(f"  >>> Finalizado input '{t}'\n")

print(f"\n--- PRUEBAS COMPLETADAS ---\n")
                               