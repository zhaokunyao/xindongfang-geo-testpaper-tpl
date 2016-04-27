# xindongfang-geo-testpaper-tpl
新东方地理试卷模板  
  
docx为原始试卷  
从docx中复制选择题部分到1中，并进行格式化  
php ./tpl.php 1 > 1.txt  
检查1.txt是否正确，并做出适当修改  
从docx中复制非选择题部分到1.txt中  
unix2dos 1.txt  
