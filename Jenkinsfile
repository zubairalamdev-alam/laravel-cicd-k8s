pipeline {
    agent any

    environment {
    DOCKERHUB_USER  = "zubairalamdev"      // your Docker Hub username
    DOCKERHUB_REPO  = "laravel-app"        // repo name only (not user/repo)
    DOCKER_CREDENTIALS = "docker-hub-creds" // must match exactly what Jenkins has
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
            env.IMAGE_TAG = "build-${BUILD_NUMBER}"   // unique per build
            docker.withRegistry('https://index.docker.io/v1/', "${DOCKER_CREDENTIALS}") {
                def appImage = docker.build("${DOCKERHUB_USER}/${DOCKERHUB_REPO}:${env.IMAGE_TAG}", ".")
                appImage.push()
                appImage.push("latest")  // optional: also update latest tag
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
                        // Update image in app-deployment.yaml
                        sh """
                        sed -i 's#image: ${DOCKERHUB_USER}/${DOCKERHUB_REPO}:.*#image: ${DOCKERHUB_USER}/${DOCKERHUB_REPO}:${IMAGE_TAG}#' app-deployment.yaml
                        """

                        sh """
                        git config user.name "Jenkins"
                        git config user.email "jenkins@cicd.local"
                        git add app-deployment.yaml
                        git commit -m "Update image to ${DOCKERHUB_USER}/${DOCKERHUB_REPO}:${IMAGE_TAG}"
                        git push origin main
                        """
                    }
                }
            }
        }
    }
}

