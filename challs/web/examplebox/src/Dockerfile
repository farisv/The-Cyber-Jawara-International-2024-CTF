FROM python:3.12.3-slim

WORKDIR /app
COPY requirements.txt requirements.txt
COPY app.py app.py
COPY templates/ templates/
RUN pip install -r requirements.txt

COPY flag.txt /flag.txt
RUN chmod 444 /flag.txt

RUN adduser --system --no-create-home tempuser
EXPOSE 20002

CMD ["python", "app.py"]