pipeline {
    agent any

    environment {
        DOCKERHUB_USER      = "zubairalamdev"
        DOCKERHUB_REPO      = "laravel-app"
        DOCKER_CREDENTIALS  = "docker-hub-creds"  // must match Jenkins credentials ID
        MANIFESTS_REPO      = "https://github.com/zubairalamdev-alam/laravel-cicd-k8s-manifests.git"
        MANIFESTS_CREDENTIALS = "github-creds"   // Jenkins GitHub credentials
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
                        appImage.push("latest")
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

                    script {
                        sh """
                        sed -i 's#image: .*#image: ${DOCKERHUB_USER}/${DOCKERHUB_REPO}:${IMAGE_TAG}#' app-deployment.yaml

                        git config user.name "Jenkins"
                        git config user.email "jenkins@cicd.local"
                        git add app-deployment.yaml
                        git commit -m "Update image to ${DOCKERHUB_USER}/${DOCKERHUB_REPO}:${IMAGE_TAG}" || echo "No changes to commit"
                        git push origin main
                        """
                    }
                }
            }
        }
    }
}
