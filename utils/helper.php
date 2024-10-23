<?php

function cek($value, $format = null)
{

    // Check for NULL or empty value
    if (empty($value)) {
        return NULL;
    }

    // Handle DateTime object
    if ($value instanceof DateTime) {
        if (is_null($format)) {
            // Return 'Y-m-d' format if no specific format is provided and not equal to '1900-01-01'
            return $value->format('Y-m-d') !== '1900-01-01' ? $value->format('Y-m-d') : NULL;
        }
        return $value->format($format);
    }

    // Handle specific string values
    if ($value === '1900-01-01' || $value === '.00') {
        return NULL;
    }

    return $value;
}

function cek_input($key)
{
    if ($_POST[$key] != NULL or $_POST[$key] != '')
        $_POST[$key] = str_replace(['"', "'"], '', $_POST[$key]);

    if ($_POST[$key] != NULL or $_POST[$key] != '') {
        return $_POST[$key];
    } else {
        return NULL;
    }
}

/** Contoh penggunaan
 * 
 * $input = "apple, banana, orange, apple, banana";
 * $result = removeDuplicatesFromString($input);
 * echo $result; // Output: "apple, banana, orange"
 *
 **/
function removeDuplicatesFromString($input) {
    // Memeriksa apakah string mengandung koma
    if (strpos($input, ',') !== false) {
        // Mengonversi string menjadi array
        $array = explode(',', $input);
        
        // Menghapus spasi di awal dan akhir setiap elemen dan menghapus duplikat
        $array = array_map('trim', $array);
        $array = array_unique($array);
        
        // Menggabungkan kembali array menjadi string
        return implode(', ', $array);
    }
    return $input; // Jika tidak ada koma, kembalikan string asli
}


?>