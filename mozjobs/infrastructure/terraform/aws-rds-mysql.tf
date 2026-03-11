resource "aws_db_subnet_group" "mysql" {
  name       = "${var.project_name}-mysql-subnet-group"
  subnet_ids = []

  tags = {
    Name = "${var.project_name}-mysql-subnet-group"
  }
}

resource "aws_db_instance" "mysql" {
  identifier             = "${var.project_name}-mysql"
  allocated_storage      = 20
  engine                 = "mysql"
  engine_version         = "8.0"
  instance_class         = "db.t3.micro"
  username               = var.db_username
  password               = var.db_password
  skip_final_snapshot    = true
  db_subnet_group_name   = aws_db_subnet_group.mysql.name
  publicly_accessible    = false
  backup_retention_period = 7
}
