const { DataTypes } = require('sequelize');
const ListingModel = (sequelize) => {
    return sequelize.define(
        'listings',
        {
          // Model attributes are defined here
            id:{
                type: DataTypes.INTEGER,
                primaryKey: true,
                allowNull: false,
            },
            image: {
                type: DataTypes.STRING,
                allowNull: false,
            },
            title: {
                type: DataTypes.STRING,
                allowNull: false,
            },
            address: {
                type: DataTypes.STRING,
                allowNull: false,
            },
            city: {
                type: DataTypes.STRING,
                allowNull: false,
            },
            zipCode: {
                type: DataTypes.STRING,
            },
            state: {
                type: DataTypes.STRING,
                allowNull: false,
            },
            country: {
                type: DataTypes.STRING,
                allowNull: false,
            },
            bed: {
                type: DataTypes.INTEGER,
                allowNull: false,
            },
            bath: {
                type: DataTypes.INTEGER,
                allowNull: false,
            },
            sqft: {
                type: DataTypes.INTEGER,
                allowNull: false,
            },
            price: {
                type: DataTypes.INTEGER,
                allowNull: false,
            },
            forRent: {
                type: DataTypes.BOOLEAN,
                allowNull: false,
            },
            tags: {
                type: DataTypes.STRING,
                allowNull: false,
            },
            propertyType: {
                type: DataTypes.STRING,
                allowNull: false,
            },
            yearBuilding: {
                type: DataTypes.INTEGER,
                allowNull: false,
            },
            featured: {
                type: DataTypes.INTEGER,
                allowNull: false,
            },
            lat: {
                type: DataTypes.DOUBLE,
                allowNull: false,
            },
            long: {
                type: DataTypes.DOUBLE,
                allowNull: false,
            },
            features:{
                type: DataTypes.STRING,
                allowNull: false,
            },
        },
        {
          timestamps:false
        },
  )
};
module.exports = ListingModel