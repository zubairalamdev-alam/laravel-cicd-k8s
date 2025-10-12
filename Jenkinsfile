pipeline {
    agent any

    environment {
        // You can set Docker registry credentials here if needed
        DOCKER_REGISTRY = "zubairalamdev"
    }

    stages {

        stage('Checkout SCM') {
            steps {
                checkout scm
            }
        }

        stage('Build & Push Docker Image') {
            steps {
                script {
                    // Get short git commit hash for tagging
                    def gitHash = sh(script: 'git rev-parse --short HEAD', returnStdout: true).trim()

                    // Build Docker image
                    docker.build(
                        "${DOCKER_REGISTRY}/laravel-cicd-app:${gitHash}", 
                        "-f docker/php-fpm.Dockerfile ./src"
                    )

                    // Optionally push the image to Docker Hub
                    withDockerRegistry([ credentialsId: 'docker-hub-credentials', url: '' ]) {
                        docker.image("${DOCKER_REGISTRY}/laravel-cicd-app:${gitHash}").push()
                    }
                }
            }
        }

        stage('Update Kubernetes Manifest') {
            steps {
                echo "Skipping for now – implement K8s update logic here"

