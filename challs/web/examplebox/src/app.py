from flask import Flask, abort, render_template, request, Response
from re import sub
from unidecode import unidecode
from urllib3.util import parse_url
import requests

app = Flask(__name__)

allowed_hostname = ["example.com"]
allowed_path = ["", "/"]
fallback = "http://example.com/"
cache = {}

def normalize(token):
    if token == None:
        token = ""
    return sub(r'\s+', '', unidecode(str(token)))

def filter_url(url):
    parsed_url = parse_url(url)
    scheme = normalize(parsed_url.scheme)
    host = normalize(parsed_url.host)
    path = normalize(parsed_url.path)
    filtered_url = url
    if not scheme.startswith('http'):
        filtered_url = fallback
    if not host in allowed_hostname:
        filtered_url = fallback
    if not path in allowed_path:
        filtered_url = fallback
    return normalize(filtered_url)

@app.route('/', methods=['GET', 'POST'])
def index():
    url = request.form.get('url', '')
    return render_template('index.html', url=url)

@app.route('/fetch_url')
def fetch_url():
    url = request.args.get('url')
    filtered_url = filter_url(url)
    try:
        if filtered_url in cache:
            response = cache[filtered_url]
        else:
            response = requests.get(filtered_url)
            cache[filtered_url] = response
        return Response(response.content,
                        status=response.status_code,
                        content_type=response.headers.get('Content-Type'))
    except requests.exceptions.RequestException as e:
        return f"Error fetching the URL: {e}", 500

@app.route('/flag')
def flag():
    if request.remote_addr != '127.0.0.1':
        abort(403)
    with open('/flag.txt', 'r') as flag:
        return flag.read()

if __name__ == '__main__':
    app.run(debug=False, host='0.0.0.0', port=20002)