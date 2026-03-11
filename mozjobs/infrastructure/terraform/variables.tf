variable "region" {
  description = "AWS region"
  type        = string
  default     = "af-south-1"
}

variable "project_name" {
  description = "Project name prefix"
  type        = string
  default     = "mozjobs"
}

variable "vpc_cidr" {
  description = "CIDR for VPC"
  type        = string
  default     = "10.40.0.0/16"
}

variable "public_subnet_cidrs" {
  description = "Public subnets"
  type        = list(string)
  default     = ["10.40.1.0/24", "10.40.2.0/24"]
}

variable "private_subnet_cidrs" {
  description = "Private subnets"
  type        = list(string)
  default     = ["10.40.11.0/24", "10.40.12.0/24"]
}

variable "db_username" {
  type    = string
  default = "mozjobs"
}

variable "db_password" {
  type      = string
  sensitive = true
  default   = "change-me-in-prod"
}
