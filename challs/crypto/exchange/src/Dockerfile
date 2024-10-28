# Use the official SageMath image
FROM sagemath/sagemath:10.4

USER root
# Update package list and install socat
RUN apt-get update && apt-get install -y socat && rm -rf /var/lib/apt/lists/*

# Set up the working directory
WORKDIR /home/sage

# Copy your Python scripts or other necessary files
COPY . /home/sage


# Install Python dependencies
ENV PATH=$PATH:/home/sage/sage/local/var/lib/sage/venv-python3.12.4/bin/
RUN pip3 install -r requirements.txt

RUN chmod +x ./run.sh

# Command to run when the container starts
CMD ["./run.sh"]