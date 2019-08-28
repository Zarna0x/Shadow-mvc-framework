pipeline {
    agent any
    stages {
        stage('Clone git repo') {
            steps {
                sh "rm -rf shdw"    
                sh "git clone -b development https://github.com/Zarna0x/Shadow-mvc-framework shdw"
            }
        }
        
        stage('Run Composer') {
            steps {
                sh "cd shdw && composer install"
            }
        }
        
        stage('Run unit test') {
            steps {
                sh "cd shdw && ./vendor/bin/phpunit"
            }
        }
    }
}