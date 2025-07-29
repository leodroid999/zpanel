const { Sequelize,DataTypes } = require('sequelize');
const sequelize = new Sequelize('estate', 'root', 'fnJ29C26fkjF', {
    host: 'localhost',
    dialect: 'mariadb',
    dialectModule: require('mariadb'),
 });

const ListingModel = require("./models/listing")
const GalleryModel = require("./models/gallery")

const Listing = ListingModel(sequelize)
const Gallery = GalleryModel(sequelize)

const initDB = async () => {
    await sequelize.authenticate()
}
module.exports={
    initDB,
    Listing,
    Gallery
}