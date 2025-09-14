# Bagisto E-commerce Application

## 🚀 Overview
Complete Bagisto e-commerce application with Docker containerization and AWS ECR integration.

## 📁 Project Structure
```
bagisto/
├── .github/workflows/     # GitHub Actions workflows
│   ├── deploy-ecr.yml    # ECR deployment and image build
│   └── destroy-ecr.yml   # ECR repository destruction
├── docker/               # Docker configuration files
│   ├── nginx.conf       # Nginx web server config
│   ├── supervisord.conf # Process supervisor config
│   └── entrypoint.sh    # Container startup script
├── terraform/           # ECR infrastructure
│   ├── backend.tf       # Terraform backend configuration
│   ├── ecr.tf          # ECR repository definition
│   └── provider.tf     # AWS provider configuration
├── Dockerfile          # Multi-stage Docker build
├── public/            # Web accessible files
│   └── info.php      # PHP configuration test
└── README.md         # This file
```

## 🛠️ Infrastructure Components

### ECR Repository
- **Name**: `bagisto-app`
- **Region**: `ap-southeast-2`
- **Image Scanning**: Enabled
- **Lifecycle Policy**: Keep last 10 images

### Docker Configuration
- **Base Image**: `php:8.3-fpm-alpine`
- **Web Server**: Nginx
- **Process Manager**: Supervisor
- **PHP Extensions**: MySQL, GD, Intl, Zip, etc.

## 🚀 Deployment Workflows

### 1. Deploy ECR and Build Image
**Trigger**: Push to main branch or manual dispatch
**Steps**:
1. Deploy ECR repository via Terraform
2. Build Docker image with latest code
3. Push image to ECR
4. Update ECS service (if exists)

### 2. Destroy ECR Repository
**Trigger**: Manual dispatch only
**Safety**: Requires typing "DESTROY-ECR"
**Action**: Removes ECR repository and all images

## 📋 Prerequisites
- AWS Account with appropriate permissions
- GitHub repository secrets configured:
  - `AWS_ACCESS_KEY_ID`
  - `AWS_SECRET_ACCESS_KEY`

## 🔧 Local Development

### Build Docker Image
```bash
docker build -t bagisto-app .
```

### Run Container
```bash
docker run -p 8080:80 \
  -e DB_HOST=your-db-host \
  -e DB_USERNAME=your-username \
  -e DB_PASSWORD=your-password \
  -e DB_DATABASE=bagisto \
  bagisto-app
```

## 🌐 Testing URLs
After deployment, access:
- **Application**: `http://your-alb-endpoint/`
- **PHP Info**: `http://your-alb-endpoint/info.php`
- **Admin Panel**: `http://your-alb-endpoint/admin`

## 🗑️ Cleanup
To destroy ECR repository:
1. Go to GitHub Actions
2. Run "Destroy ECR Repository" workflow
3. Type "DESTROY-ECR" when prompted

## 📝 Environment Variables
Container automatically receives:
- `DB_HOST` - Database endpoint
- `DB_USERNAME` - Database username  
- `DB_PASSWORD` - Database password
- `DB_DATABASE` - Database name
- `APP_URL` - Application URL

## 🔒 Security Features
- Multi-stage Docker build
- Non-root user execution
- Minimal Alpine Linux base
- Automated security scanning
- Secrets management via AWS

## 📚 Related Repositories
- **Infrastructure**: [ECS-Terraform-INFRA](../ECS-Terrfaorm-INFRA) - Complete AWS infrastructure
- **Main Application**: This repository - Bagisto application code

## 🆘 Troubleshooting
- Check GitHub Actions logs for deployment issues
- Verify AWS credentials and permissions
- Ensure ECR repository exists before ECS deployment
- Check container logs in ECS console
