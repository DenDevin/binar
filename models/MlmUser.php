<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mlm_user".
 *
 * @property int $id
 * @property int $parent_id
 * @property int $position
 * @property string $path
 * @property int $level
 */
class MlmUser extends \yii\db\ActiveRecord
{

public $childs;
    protected $root = 0;
    public static $leftNode = 1;
    public static $rightNode = 2;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mlm_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'position', 'level'], 'required'],
            [['parent_id', 'position', 'level'], 'integer'],
            [['path'], 'string', 'max' => 12288],
        ];
    }


    public function relations()
    {

        return array(
            'getparent' => array(self::BELONGS_TO, 'mlm_user', 'parent_id'),
            'childs' => array(self::HAS_MANY, 'mlm_user', 'parent_id', 'order' => 'id ASC'),
        );
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'position' => 'Position',
            'path' => 'Path',
            'level' => 'Level',
        ];
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {

            if(is_null($this->position)) {


              if($this->id > $this->parent_id){
                  $this->position = 2;
              }

                if($this->id < $this->parent_id){
                    $this->position = 1;
                }

                $path = $this->buildPath($this->id);

                array_unshift($path, $this->id);
                $lev = $path;
                $path = implode('.', array_reverse($path));
                $level = count($lev);
                $this->path = $path;
                $this->level = $level;
                $this->save();


            }


            else {

                $path = $this->buildPath($this->id);
                array_unshift($path, $this->id);
                $lev = $path;
                $path = implode('.', array_reverse($path));
                $level = count($lev);
                $this->path = $path;
                $this->level = $level;
                $this->save();

            }

        }

    }


    public function checkPosition($parent_id, $position)
    {

        if ($position == 1 || $position == 2) {

            $nodes = MlmUser::find()->where(['parent_id' => $parent_id])->all();

            if (count($nodes) == 0) {

                return 'pass';


            } elseif (count($nodes) == 1) {
                foreach ($nodes as $node) {
                    if ($node->position == $position) {
                        if ($node->position == 1) {

                           return 'leftFilled';

                        }

                        if ($node->position == 2) {

                            return 'rightFilled';
                        }

                    } else {
                        return 'pass';
                    }

                }

            } elseif (count($nodes) == 2) {

               return 'nodesFilled';
            }

        } else {

            return 'invalid';


        }


    }


    public function getRoots()
    {
        $roots = $this->find()->where(['parent_id' => null])->all();
        return $roots;
    }


    public function getTree($parent_id)
    {
        $nodes = $this->find()->where(['parent_id' => $parent_id])->all();
        if (count($nodes) > 0) {
            echo '<ul>';
            foreach ($nodes as $node) {
                echo '<li>';
                echo $node->id;
                echo '<span class="badge">';
                echo $node->path;
                echo '</span>';
                $this->getTree($node->id);
                echo '</li>';
            }
            echo '</ul>';
        }

    }





    public function getTreeUp($parent_id, $node = null)
    {
        $nodes = $this->find()->where(['parent_id' => $parent_id])->all();
        if (count($nodes) > 0) {
            echo '<ul>';
            foreach ($nodes as $node) {
                if($node->id == $parent_id) {

                }
                echo '<li>';
                echo $node->id;
                if($node->id == $parent_id) {
                    echo '<button class="btn btn-xs btn-danger">';
                    echo $node->path;
                    echo '</button>';
                }
                else {
                    echo '<span class="badge">';
                    echo $node->path;
                    echo '</span>';
                }


                $this->getTreeUp($node->id);
                echo '</li>';

            }
            echo '</ul>';
        }

    }







    public function buildPath($id, &$tree = null, $i = 0)
{
    $data =  $this->getNode($id);

    if(count($data) > 0){
        if(!is_null($id)) {

            foreach ($data as $key => &$item) {

                if ($item->parent_id != null) {

                      $tree[] = $item->parent_id;
                      $this->buildPath($item->parent_id, $tree, $i+1);


                }
            }
        }

    }



return $tree;

}

    public function getNode($id){

        $data =  $this->find()->where(['id' => $id])->all();


        return $data;
    }


    public function checkFilling($parent){

        $nodes = MlmUser::find()->where(['parent_id' => $parent])->all();

        if (count($nodes) == 0)
        {
            return 'pass';
        }

        elseif (count($nodes) == 1)

        {

            // return 'pass';

        }

        elseif (count($nodes) == 2)

        {
            return 'nodesFilled';
        }


    }





    public function returnLastID($id){
        return $id;
    }



}


