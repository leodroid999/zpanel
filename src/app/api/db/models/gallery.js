const { DataTypes } = require('sequelize');
const GalleryModel = (sequelize) => {
    return sequelize.define(
        'gallery',
        {
          // Model attributes are defined here
            id:{
                type: DataTypes.INTEGER,
                primaryKey: true,
                allowNull: false,
            },
            listing_id:{
                type: DataTypes.INTEGER,
                primaryKey: true,
                allowNull: false,
            },
            src: {
                type: DataTypes.STRING,
                allowNull: false,
            },
            alt: {
              type: DataTypes.STRING,
          },
        },
        {
          timestamps:false,
          tableName:"gallery"
        },
  )
};
module.exports = GalleryModel