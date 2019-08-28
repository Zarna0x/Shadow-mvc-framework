pipeline {
    agent any
    stages {
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