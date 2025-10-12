pipeline {
    agent any

    environment {
        DOCKERHUB_CREDENTIALS = 'dockerhub-id'   // Jenkins stored credentials
        DOCKERHUB_REPO = 'zubairalamdev/laravel-cicd-app' // Your DockerHub repo
        K8S_MANIFEST_PATH = 'kubernetes'        // Path to k8s yaml in repo
        GIT_CREDENTIALS = 'github-id'           // Jenkins stored GitHub credentials
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build & Push Docker Image') {
            steps {
                script {
                    // Get commit hash for tagging
                    def COMMIT_HASH = sh(script: 'git rev-parse --short HEAD', returnStdout: true).trim()
                    def IMAGE_TAG = "${DOCKERHUB_REPO}:${COMMIT_HASH}"

                    // Build image
                    sh "docker build -t ${IMAGE_TAG} ./src"

                    // Push image to DockerHub
                    withCredentials([usernamePassword(credentialsId: "${DOCKERHUB_CREDENTIALS}", usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                        sh "echo $DOCKER_PASS | docker login -u $DOCKER_USER --password-stdin"
                        sh "docker push ${IMAGE_TAG}"
                    }

                    // Save env vars for later
                    env.IMAGE_TAG = IMAGE_TAG
                    env.COMMIT_HASH = COMMIT_HASH
                }
            }
        }

        stage('Update Kubernetes Manifest') {
            steps {
                script {
                    sh """
                        sed -i 's|image: .*|image: ${env.IMAGE_TAG}|' ${K8S_MANIFEST_PATH}/app-deployment.yaml
                    """
                }
            }
        }

        stage('Commit & Push Changes') {
            steps {
                dir("${env.WORKSPACE}") {
                    script {
                        withCredentials([usernamePassword(credentialsId: "${GIT_CREDENTIALS}", usernameVariable: 'GIT_USER', passwordVariable: 'GIT_PASS')]) {
                            sh """
                                git config user.email "jenkins@ci.local"
                                git config user.name "Jenkins CI"
                                git add ${K8S_MANIFEST_PATH}/app-deployment.yaml
                                git commit -m "Update image tag to ${env.COMMIT_HASH} [ci skip]" || echo "No changes"
                                git push https://${GIT_USER}:${GIT_PASS}@github.com/zubairalamdev-alam/laravel-cicd-k8s.git HEAD:main
                            """
                        }
                    }
                }
            }
        }
    }

    post {
        success {
            echo "✅ Build & push completed. ArgoCD will deploy automatically to Minikube."
        }
        failure {
            echo "❌ Pipeline failed. Check logs."
        }
    }
}

