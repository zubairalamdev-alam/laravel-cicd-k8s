pipeline {
    agent any

    environment {
        DOCKERHUB_CREDENTIALS = 'dockerhub-id' // Jenkins stored credentials
        DOCKERHUB_REPO = '<DOCKERHUB_USER>/laravel-cicd-app'
        K8S_MANIFEST_PATH = 'kubernetes'       // path in repo
        GIT_CREDENTIALS = 'github-id'          // Jenkins stored credentials
    }

    stages {

        stage('Checkout') {
            steps {
                git url: 'https://github.com/zubairalam-dev/laravel-cicd-k8s.git',
                    credentialsId: "${GIT_CREDENTIALS}"
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    COMMIT_HASH = sh(script: 'git rev-parse --short HEAD', returnStdout: true).trim()
                    IMAGE_TAG = "${DOCKERHUB_REPO}:${COMMIT_HASH}"

                    sh "docker build -t ${IMAGE_TAG} ./src"
                    sh "docker login -u $DOCKERHUB_CREDENTIALS_USR -p $DOCKERHUB_CREDENTIALS_PSW"
                    sh "docker push ${IMAGE_TAG}"
                }
            }
        }

        stage('Update K8s Manifest') {
            steps {
                script {
                    // Replace image tag in deployment.yaml
                    sh """
                        sed -i '' 's|image: .*|image: ${IMAGE_TAG}|' ${K8S_MANIFEST_PATH}/app-deployment.yaml
                    """
                }
            }
        }

        stage('Push Manifest to Git') {
            steps {
                script {
                    sh """
                        git config user.email "jenkins@ci.local"
                        git config user.name "Jenkins CI"
                        git add ${K8S_MANIFEST_PATH}/app-deployment.yaml
                        git commit -m "Update image tag to ${COMMIT_HASH} [ci skip]" || echo "No changes to commit"
                        git push origin main
                    """
                }
            }
        }

    }

    post {
        success {
            echo "✅ Pipeline completed. Argo CD will deploy the new image automatically."
        }
        failure {
            echo "❌ Pipeline failed. Check Jenkins logs."
        }
    }
}

