pipeline {
    agent any

    environment {
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
                    // Get short git commit hash
                    def gitHash = sh(script: 'git rev-parse --short HEAD', returnStdout: true).trim()
                    
                    // Build Docker image
                    sh "docker build -t ${DOCKER_REGISTRY}/laravel-cicd-app:${gitHash} -f docker/php-fpm.Dockerfile ./src"

                    // Push Docker image (requires Docker credentials configured in Jenkins)
                    withDockerRegistry([credentialsId: 'docker-hub-credentials', url: '']) {
                        sh "docker push ${DOCKER_REGISTRY}/laravel-cicd-app:${gitHash}"
                    }
                }
            }
        }

        stage('Update Kubernetes Manifest') {
            steps {
                echo "Skipping for now – implement K8s update logic here"
            }
        }

        stage('Commit & Push Changes') {
            steps {
                echo "Skipping for now – implement Git commit logic if needed"
            }
        }
    }

    post {
        success {
            echo "✅ Pipeline completed successfully!"
        }
        failure {
            echo "❌ Pipeline failed. Check logs."
        }
    }
}

