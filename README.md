# Bagisto E-Commerce Platform on AWS ECS

[![Deploy Status](https://img.shields.io/badge/deploy-automated-brightgreen)](https://github.com/vishwanathacharya/ECS-BagistoV2.2.2)
[![AWS ECS](https://img.shields.io/badge/AWS-ECS%20Fargate-orange)](https://aws.amazon.com/ecs/)
[![Laravel](https://img.shields.io/badge/Laravel-10.x-red)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-blue)](https://php.net)

A production-ready, scalable Bagisto e-commerce platform deployed on AWS ECS with microservices architecture, automated CI/CD, and global CDN distribution.

## 🏗️ Architecture Overview

```
┌─────────────┐    ┌──────────────┐    ┌─────────────────┐
│   Users     │───▶│  CloudFront  │───▶│   S3 Bucket     │
│ (Global)    │    │    (CDN)     │    │ (Media Files)   │
└─────────────┘    └──────────────┘    └─────────────────┘
       │                                         ▲
       ▼                                         │
┌─────────────┐    ┌──────────────┐    ┌─────────────────┐
│     ALB     │───▶│  Web Server  │───▶│   RDS MySQL     │
│(Load Balancer)   │   (ECS)      │    │   (Cluster)     │
└─────────────┘    └──────────────┘    └─────────────────┘
                           │                     ▲
                           ▼                     │
                   ┌──────────────┐             │
                   │Queue Workers │─────────────┘
                   │   (2x ECS)   │
                   └──────────────┘
                           │
                   ┌──────────────┐
                   │  Scheduler   │
                   │   (1x ECS)   │
                   └──────────────┘
```

## 🚀 Features

### **E-Commerce Platform**
- ✅ Multi-vendor marketplace
- ✅ Product catalog management
- ✅ Order processing & inventory
- ✅ Payment gateway integration
- ✅ Customer management
- ✅ Admin dashboard

### **Cloud Infrastructure**
- ✅ **Microservices Architecture** - Separate services for web, queue, and scheduler
- ✅ **Auto Scaling** - ECS Fargate with automatic scaling
- ✅ **Global CDN** - CloudFront for fast media delivery
- ✅ **High Availability** - Multi-AZ deployment with RDS cluster
- ✅ **Security** - VPC, Security Groups, Secrets Manager
- ✅ **Cost Optimized** - Fargate Spot for non-production environments

### **DevOps & CI/CD**
- ✅ **Automated Deployment** - GitHub Actions CI/CD pipeline
- ✅ **Multi-Environment** - Dev, Staging, Production workflows
- ✅ **Container Registry** - AWS ECR with automated builds
- ✅ **Infrastructure as Code** - Terraform for reproducible deployments
- ✅ **Monitoring** - CloudWatch logs and metrics

## 📁 Project Structure

```
bagisto/
├── .github/workflows/
│   └── deploy.yml              # Multi-environment CI/CD pipeline
├── docker/
│   ├── entrypoint.sh          # Container initialization script
│   └── supervisord.conf       # Process manager configuration
├── public/
│   ├── today.php              # Health check endpoint
│   ├── test-queue.php         # Queue functionality test
│   └── test-scheduler.php     # Scheduler test endpoint
├── app/Console/Kernel.php     # Scheduled tasks configuration
├── Dockerfile                 # Single image for all services
└── README.md                  # This file
```

## 🌍 Environments

| Environment | Branch | Compute | Database | Deployment |
|-------------|--------|---------|----------|------------|
| **Development** | `dev` | Fargate Spot | db.r5.large | Automatic on push |
| **Staging** | `staging` | Fargate Spot | db.r5.large | Automatic on push |
| **Production** | `production` | Fargate | db.r5.large | Automatic on push |

## 🔧 Services Architecture

### **Web Server** (1 container)
- **Technology:** nginx + php-fpm
- **Purpose:** Handle HTTP requests, serve web application
- **Scaling:** Auto-scaling based on CPU/memory usage

### **Queue Workers** (2 containers)
- **Technology:** Laravel Queue with database driver
- **Purpose:** Process background jobs (emails, image processing, payments)
- **Scaling:** Independent scaling based on queue depth

### **Scheduler** (1 container)
- **Technology:** Laravel Task Scheduler
- **Purpose:** Run cron jobs and automated tasks
- **Examples:** Currency updates, abandoned cart emails, cleanup tasks

### **Database**
- **Technology:** Amazon RDS MySQL Cluster
- **Configuration:** Writer + Reader instances
- **Backup:** Automated daily backups with point-in-time recovery

### **Storage & CDN**
- **Media Storage:** Amazon S3 with lifecycle policies
- **CDN:** CloudFront for global content delivery
- **Caching:** Optimized for images (JPG, PNG, WebP)

## 🚀 Quick Start

### **Prerequisites**
- AWS Account with appropriate permissions
- GitHub repository access
- Docker installed locally (for development)

### **Environment Setup**

1. **Clone Repository**
   ```bash
   git clone https://github.com/vishwanathacharya/ECS-BagistoV2.2.2.git
   cd ECS-BagistoV2.2.2
   ```

2. **Configure AWS Credentials**
   ```bash
   # Add to GitHub Secrets
   AWS_ACCESS_KEY_ID=your-access-key
   AWS_SECRET_ACCESS_KEY=your-secret-key
   ```

3. **Deploy Infrastructure**
   ```bash
   # Deploy to staging environment
   git checkout staging
   git push origin staging
   
   # Deploy to production
   git checkout production  
   git push origin production
   ```

### **Local Development**

```bash
# Build and run locally
docker build -t bagisto-local .
docker run -p 8080:80 bagisto-local

# Access application
open http://localhost:8080
```

## 📊 Monitoring & Testing

### **Health Check Endpoints**
- `/today.php` - Application health status
- `/test-queue.php` - Queue system functionality
- `/test-scheduler.php` - Scheduled tasks status
- `/info.php` - PHP configuration details

### **Logs & Monitoring**
- **Application Logs:** CloudWatch `/ecs/bagisto-{env}`
- **Queue Logs:** CloudWatch `/ecs/bagisto-{env}-queue`
- **Scheduler Logs:** CloudWatch `/ecs/bagisto-{env}-scheduler`

### **Performance Metrics**
- **Response Time:** < 200ms (cached content)
- **Availability:** 99.9% uptime SLA
- **Scalability:** Auto-scaling from 1-10 containers
- **Global Latency:** < 100ms via CloudFront

## 🔒 Security Features

- **VPC Isolation:** Private subnets for application containers
- **Security Groups:** Restrictive firewall rules
- **Secrets Management:** AWS Secrets Manager for database credentials
- **HTTPS Enforcement:** SSL/TLS termination at load balancer
- **IAM Roles:** Least privilege access for ECS tasks

## 💰 Cost Optimization

- **Fargate Spot:** 70% cost savings for non-production
- **S3 Lifecycle:** Automatic transition to cheaper storage classes
- **CloudFront Caching:** Reduced origin requests
- **Right-sizing:** Optimized container resources (256 CPU, 512 MB)

## 🔄 CI/CD Pipeline

### **Deployment Flow**
```
Code Push → Branch Detection → Environment Selection → Build Image → 
Push to ECR → Update ECS Services → Health Check → Deployment Complete
```

### **Branch Strategy**
- `main` - Code storage (no deployment)
- `dev` - Development environment
- `staging` - Staging environment  
- `production` - Production environment

## 📈 Scaling Strategy

### **Horizontal Scaling**
- **Web Servers:** 1-5 containers based on CPU usage
- **Queue Workers:** 1-10 containers based on queue depth
- **Database:** Read replicas for read-heavy workloads

### **Vertical Scaling**
- **Development:** 256 CPU, 512 MB RAM
- **Staging:** 512 CPU, 1024 MB RAM
- **Production:** 1024 CPU, 2048 MB RAM

## 🛠️ Maintenance

### **Regular Tasks**
- **Database Backups:** Automated daily
- **Log Rotation:** 7-30 days retention
- **Security Updates:** Monthly container rebuilds
- **Performance Review:** Quarterly optimization

### **Disaster Recovery**
- **RTO:** 15 minutes (Recovery Time Objective)
- **RPO:** 1 hour (Recovery Point Objective)
- **Multi-AZ:** Automatic failover capability

## 📞 Support

### **Infrastructure Repository**
[ECS-Terraform-INFRA](https://github.com/vishwanathacharya/ECS-Terrfaorm-INFRA)

### **Documentation**
- [AWS ECS Documentation](https://docs.aws.amazon.com/ecs/)
- [Bagisto Documentation](https://bagisto.com/en/documentation/)
- [Laravel Documentation](https://laravel.com/docs)

### **Monitoring Dashboards**
- AWS CloudWatch Console
- ECS Service Health Dashboard
- Application Performance Metrics

---

**Built with ❤️ using AWS ECS, Laravel, and modern DevOps practices**

*Last Updated: September 2025*
