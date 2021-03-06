<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use DateTime;

/**
 * Class ProductPriceHistory
 * @package common\models
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $value
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * @property Product $product
 */
class ProductPriceHistory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_price_history';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            ['value', 'integer'],
            [
                'product_id',
                'exist',
                'targetClass' => Product::class,
                'targetAttribute' => ['product_id' => 'id']
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
