// Jenkinsfile (Declarative Pipeline)

pipeline {
    agent any
    
    // --- Environment Variables ---
    environment {
        // Application and Registry Details
        DOCKER_REGISTRY = 'docker.io'
        DOCKER_USERNAME = 'zubairalamdev'
        DOCKER_IMAGE_NAME = "laravel-app" // Name of the image on Docker Hub

        // The image tag will be unique using the Jenkins build number
        IMAGE_TAG = "build-${env.BUILD_NUMBER}"
        FULL_IMAGE = "${DOCKER_USERNAME}/${DOCKER_IMAGE_NAME}:${IMAGE_TAG}"

        // GitOps Manifest Repository Details (replace with your actual ArgoCD repo)
        GITOPS_REPO_URL = 'git@github.com:zubairalamdev-alam/laravel-cicd-k8s-manifests.git' // *** ASSUMED REPO NAME ***
        GITOPS_REPO_CREDENTIALS = 'github-ssh-key-for-gitops' // Jenkins Credential ID for GitOps Repo access
        
        // Path to your deployment file *within* the GitOps Manifests Repository
        K8S_DEPLOYMENT_FILE_PATH = 'k8s/deployment.yaml' 
    }

    stages {
        
        stage('Checkout Source Code') {
            steps {
                // Clones the application code (where the Dockerfile resides)
                echo "Cloning Application Code: ${env.JOB_NAME}"
                checkout scm 
            }
        }
        
        stage('Build Docker Image') {
            // NOTE: Jenkins agent must have access to the Docker daemon (e.g., /var/run/docker.sock mounted)
            steps {
                echo "Building image: ${FULL_IMAGE}"
                sh "docker build -t ${FULL_IMAGE} ." 
            }
        }
        
        stage('Push Image to Docker Hub') {
    steps {
        // Use the ID created in Jenkins Credentials
        withCredentials([usernamePassword(credentialsId: 'docker-hub-creds', passwordVariable: 'DOCKER_PASSWORD', usernameVariable: 'DOCKER_USERNAME')]) {
            // 1. Log in to Docker Hub
            sh "echo ${DOCKER_PASSWORD} | docker login -u ${DOCKER_USERNAME} --password-stdin"

            // 2. Push the built image
            sh "docker push ${FULL_IMAGE}"

            // 3. Log out (optional but good practice)
            sh "docker logout"
        }
    }
}
        
        stage('Update GitOps Repo (CD Trigger)') {
            // Use the Docker agent itself or an agent with Git installed
            agent any 
            
            steps {
                // 1. Clone the GitOps Manifests repository
                echo "Cloning GitOps Manifests Repo..."
                // Use the configured SSH Key credential
                git url: "${GITOPS_REPO_URL}", credentialsId: "${GITOPS_REPO_CREDENTIALS}"

                // 2. Modify the Kubernetes Deployment file
                echo "Updating ${K8S_DEPLOYMENT_FILE_PATH} with new image tag: ${FULL_IMAGE}"
                
                // IMPORTANT: This 'sed' command is used to replace the image path in the deployment YAML.
                // It assumes the image line in your deployment.yaml looks like: 'image: zubairalamdev/laravel-app:old-tag'
                // The 'sed -i.bak' is a common syntax for macOS/BSD 'sed'
                sh """
                sed -i.bak 's|image: ${DOCKER_USERNAME}/${DOCKER_IMAGE_NAME}:.*|image: ${FULL_IMAGE}|' ${K8S_DEPLOYMENT_FILE_PATH}
                rm ${K8S_DEPLOYMENT_FILE_PATH}.bak
                """

                // 3. Commit and Push the change (this triggers ArgoCD)
                echo "Committing and pushing change to GitOps Repo..."
                sh """
                git config user.name "Jenkins CI/CD Pipeline"
                git config user.email "jenkins-ci@example.com"
                git add ${K8S_DEPLOYMENT_FILE_PATH}
                git commit -m "Deployment Trigger: New image ${FULL_IMAGE} deployed by Jenkins Build #${env.BUILD_NUMBER}"
                git push origin HEAD
                """
            }
        }
    }
    
    post {
        always {
            // Cleanup any unused containers/images if necessary
            script {
                // You can add cleanup commands here if you run containers during the build
            }
        }
    }
}
