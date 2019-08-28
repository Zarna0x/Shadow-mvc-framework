pipeline {
    agent any
    stages {
        stage('Remove old workspace') {
            steps {
                sh "rm -rf *"    
            }
        }
        
        stage('Run Composer') {
            steps {
                sh "composer install"
            }
        }
        
        stage('Run unit test') {
            steps {
                sh "./vendor/bin/phpunit"
            }
        }
    }
}