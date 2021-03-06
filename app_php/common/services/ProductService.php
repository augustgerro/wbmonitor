<?php

namespace common\services;

use common\models\Product;
use common\services\exceptions\ProductServiceException;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * Class ProductService
 * @package common\services
 */
class ProductService extends Component
{
    /**
     * @param string $url
     * @return Product
     * @throws ProductServiceException
     */
    public function addProduct(string $url)
    {
        $result = $this->parseProductUrl($url);

        $product = new Product([
            'domain' => $result['domain'],
            'code' => $result['code'],
        ]);

        if (!$product->save()) {
            throw new ProductServiceException(Yii::t('app', 'Ошибка при сохранении товара'));
        }

        return $product;
    }

    /**
     * @param string $domain
     * @param int $code
     * @return array|null|\yii\db\ActiveRecord|Product
     */
    public function getProductByCode(string $domain, int $code)
    {
        return Product::find()->where(['domain' => $domain, 'code' => $code])->limit(1)->one();
    }

    /**
     * @param string $url
     * @return Product|null
     * @throws ProductServiceException
     */
    public function getProductByUrl(string $url)
    {
        $result = $this->parseProductUrl($url);

        return $this->getProductByCode($result['domain'], $result['code']);
    }

    /**
     * @param string $url
     * @return array
     * @throws ProductServiceException
     */
    public function parseProductUrl(string $url)
    {
        preg_match(
            '/https:\/\/[www.]*wildberries.(?<domain>\w{2})\/catalog\/(?<code>\d+)\/detail.aspx/',
            $url,
            $matches
        );

        if (!isset($matches['domain']) || !isset($matches['code'])) {
            throw new ProductServiceException(Yii::t('app', 'Некорректный URL: {url} ', ['url' => $url]));
        }

        return [
            'domain' => $matches['domain'],
            'code' => $matches['code'],
        ];
    }

    /**
     * @param Product $product
     * @return string
     */
    public function buildUrl(Product $product)
    {
        return "https://www.wildberries.{$product->domain}/catalog/{$product->code}/detail.aspx";
    }

    /**
     * @param Product $product
     * @return string
     */
    public function getProductCurrencyCode(Product $product)
    {
        return $this->getDomainCurrencyCode($product->domain);
    }

    /**
     * @param string $domain
     * @return string
     */
    public function getDomainCurrencyCode(string $domain)
    {
        return ArrayHelper::getValue([
            'kz' => 'KZT',
            'ru' => 'RUB',
        ], $domain);
    }
}
