<?php

use http\Client\Curl\User;

require_once 'file_works.php';
require_once 'validation_logic.php';
$arrErr = [];
$arrErr['menu_name'] = $arrErr['category_id'] = $arrErr['price'] = $arrErr['recipe'] = $arrErr['file_name'] = '';

$menu_name = $category_id = $price = $recipe = $file_name = '';
$second_data = [];
$id = null;
$data = null;

if(isset($_GET['id'])){
    $id= $_GET['id'];
    $data = UserTable::get_by_id($id);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(!empty($_POST['closeEdit'])){
        header('Location: interfaces.php');
    }

    if(!empty($_POST['applyEdit'])){



        if(!empty($_POST['menu_name'])){
            $menu_name = validation('menu_name', $_POST['menu_name']);
            if(!$menu_name) $arrErr['menu_name'] = 'Некорректное название А-Я а-я Ёё';
            $second_data['name'] = $menu_name;
        } else $arrErr['menu_name'] = 'Введите название';

        if(!empty($_POST['category_id'])){
            $category_id = validation('category_id', $_POST['category_id']);
            if(!$category_id) $arrErr['category_id'] = 'Некорректно выбрана категория';
            $second_data['id_category'] = $category_id;
        } else $arrErr['category_id'] = 'Выберите категорию';

        if(!empty($_POST['price'])){
            $price = validation('price', $_POST['price']);
            if(!$price) $arrErr['price'] = 'Некорректно указана цена';
            $second_data['cost'] = $price;
        } else $arrErr['price'] = 'Введите цену';

        if(!empty($_POST['recipe'])){
            $recipe = validation('recipe', $_POST['recipe']);
            if(!$recipe) $arrErr['recipe'] = 'Некорректно указана рецептура';
            $second_data['recipe'] = $recipe;

        } else $arrErr['recipe'] = 'Введите рецептуру';

        if($menu_name && $category_id && $price && $recipe){
            $file_name = load_and_getFileName();

            if($file_name == '00'){
                $arrErr['file_name'] = 'Файл не выбран';
                $file_name = false;
            }
            if($file_name == '01'){
                $arrErr['file_name'] = 'Недопустимый тип файла';
                $file_name = false;
            }
            if($file_name){
                UserTable::replace_by_id($id, $menu_name, $category_id, $price, $recipe, $file_name);
                unlink('../img/pizza/' . $data['img_path']);
                header('Location: interfaces.php');
            }
        }

    }

    if(!empty($_POST['reset'])){
        $second_data = null;
    }

}

