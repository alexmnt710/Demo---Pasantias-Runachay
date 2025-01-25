FROM python:3.8

WORKDIR /app

COPY requirements.txt .

RUN pip install --upgrade pip
RUN pip install --no-cache-dir -r requirements.txt
RUN pip install --no-cache-dir flask==2.0.3 Werkzeug==2.0.3

COPY . .

CMD ["python", "app.py"]

