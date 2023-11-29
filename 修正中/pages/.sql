select 
gt.id
,gt.game_title
,gt.game_date
,gt.game_description
,gt.insert_at
,git.folder_name
,git.file_name
,tt.type_name
,ct.category_name
from game_tb gt
LEFT JOIN game_imageload_tb git ON gt.id = git.games_no
LEFT JOIN type_tb tt ON gt.id = tt.type_no
LEFT JOIN category_tb ct ON gt.id = ct.category_no
WHERE ct.category_name = category_name;