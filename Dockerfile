# Dockerfile for Jenkins with Docker CLI and Git
FROM jenkins/jenkins:lts

# Switch to root user to install necessary packages
USER root

# Install Git, wget, SUDO, and the Docker CLI static binary directly (all in one RUN block)
RUN apt-get update && \
    apt-get install -y git wget sudo && \
    wget -qO /usr/local/bin/docker https://download.docker.com/linux/static/stable/x86_64/docker-26.1.1.tgz && \
    tar -xzf /usr/local/bin/docker -C /tmp/ && \
    mv /tmp/docker/docker /usr/local/bin/docker && \
    chmod +x /usr/local/bin/docker && \
    rm -rf /tmp/docker /usr/local/bin/docker-26.1.1.tgz && \
    # Ensure jenkins user can run docker commands without password (required by sudo)
    echo "jenkins ALL=NOPASSWD: /usr/local/bin/docker" > /etc/sudoers.d/jenkins-docker && \
    chmod 0440 /etc/sudoers.d/jenkins-docker

# Switch back to the jenkins user
USER jenkins
