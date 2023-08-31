module "boot" {
    source = "modules/core-functionality"
    config = file("config.yaml")
}
