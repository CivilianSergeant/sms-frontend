select id,description,region_l1_code, region_l2_code,region_l3_code,region_l4_code 
from
(
select a.region_l1_name description,a.id,a.region_l1_code,a.region_l2_code,a.region_l3_code,a.region_l4_code 
from region_level_1 a
union
select b.region_l2_name description,b.id,b.region_l1_code,b.region_l2_code,b.region_l3_code,b.region_l4_code 
from region_level_2 b
union
select d.region_l3_name description,d.id,d.region_l1_code,d.region_l2_code,d.region_l3_code,d.region_l4_code 
from region_level_3 d

) c
order by region_l1_code,region_l2_code,region_l3_code,region_l4_code 




