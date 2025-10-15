pipeline {
    agent any

    environment {
        DOCKERHUB_USER      = "zubairalamdev"
        DOCKERHUB_REPO      = "laravel-app"
        DOCKER_CREDENTIALS  = "docker-hub-creds"  // must match Jenkins credentials ID
        MANIFESTS_REPO      = "https://github.com/zubairalamdev-alam/laravel-cicd-k8s-manifests.git"
        MANIFESTS_CREDENTIALS = "github-ssh-key-for-gitops"   // Jenkins GitHub credentials
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
    dir('manifests') {
        git branch: 'main',
            url: 'https://github.com/zubairalamdev-alam/laravel-cicd-k8s-manifests.git',
            credentialsId: 'github-ssh-key-for-gitops'

        sh """
          sed -i 's|image: .*|image: zubairalamdev/laravel-app:build-${BUILD_NUMBER}|' app-deployment.yaml
          git config user.email "jenkins@cicd.com"
          git config user.name "Jenkins CI"
          git add app-deployment.yaml
          git commit -m "Update image to build-${BUILD_NUMBER}"
          git push origin main
        """
    }
}

            }
        }
    }
}
