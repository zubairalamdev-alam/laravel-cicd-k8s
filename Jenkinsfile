pipeline {
    agent any

    environment {
        APP_NAME = 'laravel-app'
        DOCKER_IMAGE = 'my-laravel-image:latest'
    }

   stage('Checkout') {
    steps {
        git branch: 'main', url: 'https://github.com/zubairalamdev-alam/laravel-cicd-k8s.git'
    }
}


        stage('Build') {
            steps {
                script {
                    echo 'Building Docker image...'
                    sh 'docker build -t $DOCKER_IMAGE .'
                }
            }
        }

        stage('Test') {
            steps {
                script {
                    echo 'Running tests...'
                    sh 'docker run --rm $DOCKER_IMAGE php artisan test'
                }
            }
        }

        stage('Deploy') {
            steps {
                script {
                    echo 'Deploying application...'
                    sh """
                        kubectl apply -f k8s/deployment.yaml
                        kubectl rollout status deployment/$APP_NAME
                    """
                }
            }
        }
    }

    post {
        success {
            echo 'Pipeline completed successfully!'
        }
        failure {
            echo 'Pipeline failed.'
        }
    }
}
