-- Run these queries in Supabase SQL editor to capture the exact schema
select table_name, column_name, data_type, is_nullable
from information_schema.columns
where table_schema = 'public'
  and table_name in ('offers', 'products', 'product_actives', 'view_offer_active_metrics')
order by table_name, ordinal_position;

-- Identify foreign key relationships
select tc.table_name, kcu.column_name, ccu.table_name as foreign_table_name, ccu.column_name as foreign_column_name
from information_schema.table_constraints as tc
join information_schema.key_column_usage as kcu
  on tc.constraint_name = kcu.constraint_name
join information_schema.constraint_column_usage as ccu
  on ccu.constraint_name = tc.constraint_name
where tc.constraint_type = 'FOREIGN KEY'
  and tc.table_schema = 'public'
  and tc.table_name in ('offers', 'products', 'product_actives')
order by tc.table_name;

-- Check view definitions (if accessible)
select table_name, view_definition
from information_schema.views
where table_schema = 'public'
  and table_name = 'view_offer_active_metrics';
