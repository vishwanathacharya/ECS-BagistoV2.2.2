terraform {
  backend "s3" {
    bucket = "bagisto-terraform-state-bucket"
    key    = "ecr/terraform.tfstate"
    region = "ap-southeast-2"
  }
}
