pipeline {
    agent any

    environment {
        DOCKERHUB_REPO   = "zubairalamdev/laravel-app"
        MANIFESTS_REPO   = "git@github.com:zubairalamdev-alam/laravel-cicd-k8s-manifests.git"
        DEPLOYMENT_FILE  = "app-deployment.yaml"
        K8S_NAMESPACE    = "laravel-app"
    }

    stages {
        stage('Checkout Code') {
            steps {
                checkout scm
            }
        }

        stage('Build & Push Docker Image') {
            steps {
                script {
                    def imageTag = "build-${BUILD_NUMBER}"
                    sh """
                        docker build -t ${DOCKERHUB_REPO}:${imageTag} .
                        docker tag ${DOCKERHUB_REPO}:${imageTag} ${DOCKERHUB_REPO}:latest
                        docker login -u "\$DOCKER_USER" -p "\$DOCKER_PASS"
                        docker push ${DOCKERHUB_REPO}:${imageTag}
                        docker push ${DOCKERHUB_REPO}:latest
                    """
                    env.IMAGE_TAG = imageTag
                }
            }
        }

        stage('Update Manifests Repo') {
            steps {
                dir('manifests') {
                    git branch: 'main',
                        url: "${MANIFESTS_REPO}",
                        credentialsId: 'github-ssh-key-for-gitops'

                    script {
                        sh """
                            echo "🔹 Updating image in ${DEPLOYMENT_FILE}"
                            sed -i 's#${DOCKERHUB_REPO}:.*#${DOCKERHUB_REPO}:${IMAGE_TAG}#' ${DEPLOYMENT_FILE}

                            git config user.email "jenkins@ci-cd.local"
                            git config user.name "Jenkins CI"
                            git add ${DEPLOYMENT_FILE}
                            git commit -m "Update image to ${DOCKERHUB_REPO}:${IMAGE_TAG} [ci skip]" || echo "No changes to commit"
                            git push origin main
                        """
                    }
                }
            }
        }
    }
}
