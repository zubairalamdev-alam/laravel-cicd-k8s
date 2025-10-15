pipeline {
    agent any

    environment {
        DOCKERHUB_USER        = "zubairalamdev"
        DOCKERHUB_REPO        = "laravel-app"
        DOCKER_CREDENTIALS    = "docker-hub-creds"   // Jenkins DockerHub credentials ID
        MANIFESTS_REPO        = "git@github.com:zubairalamdev-alam/laravel-cicd-k8s-manifests.git"
        MANIFESTS_CREDENTIALS = "github-ssh-key-for-gitops"   // Jenkins GitHub SSH credentials
    }

    stages {
        stage('Checkout App Repo') {
            steps {
                git branch: 'main',
                    url: 'https://github.com/zubairalamdev-alam/laravel-cicd-k8s.git'
            }
        }

        stage('Build & Push Docker Image') {
            steps {
                script {
                    env.IMAGE_TAG = "build-${BUILD_NUMBER}"
                    docker.withRegistry('https://index.docker.io/v1/', "${DOCKER_CREDENTIALS}") {
                        def appImage = docker.build("${DOCKERHUB_USER}/${DOCKERHUB_REPO}:${env.IMAGE_TAG}", ".")
                        appImage.push()
                        appImage.push("latest") // keep "latest" for fallback
                    }
                }
            }
        }

        stage('Update Manifests Repo') {
            steps {
                dir('manifests') {
                    git branch: 'main',
                        url: "${MANIFESTS_REPO}",
                        credentialsId: "${MANIFESTS_CREDENTIALS}"

                    sh '''
                    # Safely replace only the image tag in app-deployment.yaml
                    sed -i "s|image: ${DOCKERHUB_USER}/${DOCKERHUB_REPO}:.*|image: ${DOCKERHUB_USER}/${DOCKERHUB_REPO}:build-${BUILD_NUMBER}|" app-deployment.yaml

                    git config user.email "ci-bot@example.com"
                    git config user.name "Jenkins CI"

                    git add app-deployment.yaml
                    git commit -m "Update image tag to build-${BUILD_NUMBER}" || echo "No changes to commit"
                    git push origin main
                    '''
                }
            }
        }
    }
}
