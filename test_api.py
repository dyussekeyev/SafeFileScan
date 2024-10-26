import requests
import sys
import argparse

def get_scans(api_key, base_url):
    response = requests.get(f"{base_url}/api/get_scans.php", headers={"Authorization": f"Bearer {api_key}"})
    response.raise_for_status()
    return response.json()

def get_file(api_key, base_url, file_id):
    response = requests.get(f"{base_url}/api/get_file.php", headers={"Authorization": f"Bearer {api_key}"}, params={"id": file_id})
    response.raise_for_status()
    return response.content

def put_scan(api_key, base_url, file_id, verdict):
    response = requests.post(f"{base_url}/api/put_scan.php", headers={"Authorization": f"Bearer {api_key}"}, data={"id": file_id, "verdict": verdict})
    response.raise_for_status()
    return response.json()

def main():
    parser = argparse.ArgumentParser(description="Test API Endpoints")
    parser.add_argument("api_key", type=str, help="API Key for authentication")
    parser.add_argument("base_url", type=str, help="Base URL of the API")

    args = parser.parse_args()

    try:
        # Get list of IDs
        scans = get_scans(args.api_key, args.base_url)
        for scan in scans:
            file_id = scan['id']
            # Download file
            file_content = get_file(args.api_key, args.base_url, file_id)
            file_size = len(file_content)
            # Send result
            verdict = f"Dummy result - {file_size}"
            put_scan(args.api_key, args.base_url, file_id, verdict)
            print(f"Processed file ID {file_id} with size {file_size} bytes.")
    
    except requests.HTTPError as http_err:
        print(f"HTTP error occurred: {http_err}")
    except Exception as err:
        print(f"An error occurred: {err}")

if __name__ == "__main__":
    main()
