insert into public.actives (slug, default_unit, category)
values
    ('vitamin-d', 'µg', 'vitamin'),
    ('magnesium', 'mg', 'mineral'),
    ('creatine', 'g', 'sports')
on conflict do nothing;

insert into public.active_translations (active_id, lang, name, description_short, description_long, meta_title, meta_description)
select id, 'nl', 'Vitamine D', 'Ondersteunt botten en spieren.', 'Vitamine D ondersteunt de calciumhuishouding en spierfunctie.', 'Vitamine D uitleg', 'Vitamine D uitleg, dosering en vergelijking.' from public.actives where slug = 'vitamin-d'
on conflict do nothing;
insert into public.active_translations (active_id, lang, name, description_short, description_long, meta_title, meta_description)
select id, 'en', 'Vitamin D', 'Supports bones and muscles.', 'Vitamin D supports calcium balance and muscle function.', 'Vitamin D guide', 'Vitamin D overview, dosing, and comparison.' from public.actives where slug = 'vitamin-d'
on conflict do nothing;
insert into public.active_translations (active_id, lang, name, description_short, description_long, meta_title, meta_description)
select id, 'de', 'Vitamin D', 'Unterstützt Knochen und Muskeln.', 'Vitamin D unterstützt den Calciumhaushalt und Muskelfunktion.', 'Vitamin D Leitfaden', 'Vitamin D Übersicht, Dosierung und Vergleich.' from public.actives where slug = 'vitamin-d'
on conflict do nothing;

insert into public.active_translations (active_id, lang, name, description_short, description_long, meta_title, meta_description)
select id, 'nl', 'Magnesium', 'Ondersteunt spier- en zenuwfunctie.', 'Magnesium is betrokken bij de energiestofwisseling.', 'Magnesium uitleg', 'Magnesium vormen en vergelijking.' from public.actives where slug = 'magnesium'
on conflict do nothing;
insert into public.active_translations (active_id, lang, name, description_short, description_long, meta_title, meta_description)
select id, 'en', 'Magnesium', 'Supports muscle and nerve function.', 'Magnesium is involved in energy metabolism.', 'Magnesium guide', 'Magnesium forms and comparison.' from public.actives where slug = 'magnesium'
on conflict do nothing;
insert into public.active_translations (active_id, lang, name, description_short, description_long, meta_title, meta_description)
select id, 'de', 'Magnesium', 'Unterstützt Muskel- und Nervenfunktion.', 'Magnesium ist am Energiestoffwechsel beteiligt.', 'Magnesium Leitfaden', 'Magnesium Formen und Vergleich.' from public.actives where slug = 'magnesium'
on conflict do nothing;

insert into public.active_translations (active_id, lang, name, description_short, description_long, meta_title, meta_description)
select id, 'nl', 'Creatine', 'Ondersteunt explosieve kracht.', 'Creatine monohydraat is de best onderzochte vorm.', 'Creatine uitleg', 'Creatine monohydraat en vergelijking.' from public.actives where slug = 'creatine'
on conflict do nothing;
insert into public.active_translations (active_id, lang, name, description_short, description_long, meta_title, meta_description)
select id, 'en', 'Creatine', 'Supports high-intensity performance.', 'Creatine monohydrate is the most studied form.', 'Creatine guide', 'Creatine monohydrate and comparison.' from public.actives where slug = 'creatine'
on conflict do nothing;
insert into public.active_translations (active_id, lang, name, description_short, description_long, meta_title, meta_description)
select id, 'de', 'Kreatin', 'Unterstützt intensive Leistung.', 'Kreatin-Monohydrat ist am besten untersucht.', 'Kreatin Leitfaden', 'Kreatin-Monohydrat und Vergleich.' from public.actives where slug = 'creatine'
on conflict do nothing;

insert into public.forms (slug)
values
    ('d3'),
    ('d2'),
    ('citrate'),
    ('bisglycinate'),
    ('monohydrate')
on conflict do nothing;

insert into public.form_translations (form_id, lang, name, description)
select id, 'nl', 'D3', 'D3 (cholecalciferol) wordt meestal beter opgenomen.' from public.forms where slug = 'd3'
on conflict do nothing;
insert into public.form_translations (form_id, lang, name, description)
select id, 'en', 'D3', 'D3 (cholecalciferol) is commonly preferred.' from public.forms where slug = 'd3'
on conflict do nothing;
insert into public.form_translations (form_id, lang, name, description)
select id, 'de', 'D3', 'D3 (Cholecalciferol) wird meist bevorzugt.' from public.forms where slug = 'd3'
on conflict do nothing;

insert into public.form_translations (form_id, lang, name, description)
select id, 'nl', 'D2', 'D2 is een plantaardige optie.' from public.forms where slug = 'd2'
on conflict do nothing;
insert into public.form_translations (form_id, lang, name, description)
select id, 'en', 'D2', 'D2 is a plant-based option.' from public.forms where slug = 'd2'
on conflict do nothing;
insert into public.form_translations (form_id, lang, name, description)
select id, 'de', 'D2', 'D2 ist eine pflanzliche Option.' from public.forms where slug = 'd2'
on conflict do nothing;

insert into public.form_translations (form_id, lang, name, description)
select id, 'nl', 'Citraat', 'Magnesiumcitraat wordt vaak goed opgenomen.' from public.forms where slug = 'citrate'
on conflict do nothing;
insert into public.form_translations (form_id, lang, name, description)
select id, 'en', 'Citrate', 'Magnesium citrate is commonly used.' from public.forms where slug = 'citrate'
on conflict do nothing;
insert into public.form_translations (form_id, lang, name, description)
select id, 'de', 'Citrat', 'Magnesiumcitrat ist weit verbreitet.' from public.forms where slug = 'citrate'
on conflict do nothing;

insert into public.form_translations (form_id, lang, name, description)
select id, 'nl', 'Bisglycinaat', 'Magnesiumbisglycinaat is mild voor de maag.' from public.forms where slug = 'bisglycinate'
on conflict do nothing;
insert into public.form_translations (form_id, lang, name, description)
select id, 'en', 'Bisglycinate', 'Magnesium bisglycinate is gentle.' from public.forms where slug = 'bisglycinate'
on conflict do nothing;
insert into public.form_translations (form_id, lang, name, description)
select id, 'de', 'Bisglycinat', 'Magnesiumbisglycinat gilt als magenfreundlich.' from public.forms where slug = 'bisglycinate'
on conflict do nothing;

insert into public.form_translations (form_id, lang, name, description)
select id, 'nl', 'Monohydraat', 'De meest onderzochte creatinevorm.' from public.forms where slug = 'monohydrate'
on conflict do nothing;
insert into public.form_translations (form_id, lang, name, description)
select id, 'en', 'Monohydrate', 'Most studied creatine form.' from public.forms where slug = 'monohydrate'
on conflict do nothing;
insert into public.form_translations (form_id, lang, name, description)
select id, 'de', 'Monohydrat', 'Am besten untersuchte Kreatinform.' from public.forms where slug = 'monohydrate'
on conflict do nothing;

insert into public.active_forms (active_id, form_id)
select actives.id, forms.id
from public.actives
join public.forms on (actives.slug = 'vitamin-d' and forms.slug in ('d2', 'd3'))
on conflict do nothing;

insert into public.active_forms (active_id, form_id)
select actives.id, forms.id
from public.actives
join public.forms on (actives.slug = 'magnesium' and forms.slug in ('citrate', 'bisglycinate'))
on conflict do nothing;

insert into public.active_forms (active_id, form_id)
select actives.id, forms.id
from public.actives
join public.forms on (actives.slug = 'creatine' and forms.slug = 'monohydrate')
on conflict do nothing;

insert into public.goals (slug)
values
    ('sleep'),
    ('recovery'),
    ('bones'),
    ('immunity')
on conflict do nothing;

insert into public.goal_translations (goal_id, lang, name, description)
select id, 'nl', 'Slaap', 'Supplementen die vaak gekozen worden voor slaap.' from public.goals where slug = 'sleep'
on conflict do nothing;
insert into public.goal_translations (goal_id, lang, name, description)
select id, 'en', 'Sleep', 'Supplements often chosen for sleep.' from public.goals where slug = 'sleep'
on conflict do nothing;
insert into public.goal_translations (goal_id, lang, name, description)
select id, 'de', 'Schlaf', 'Supplemente, die oft für Schlaf gewählt werden.' from public.goals where slug = 'sleep'
on conflict do nothing;

insert into public.active_goals (active_id, goal_id)
select actives.id, goals.id
from public.actives
join public.goals on (actives.slug = 'vitamin-d' and goals.slug in ('bones', 'immunity'))
on conflict do nothing;

insert into public.recommended_doses (active_id, audience, min_value, max_value, unit, notes, region)
select id, 'general', 10, 20, 'µg', 'Algemene richtlijn', 'NL' from public.actives where slug = 'vitamin-d'
on conflict do nothing;
insert into public.recommended_doses (active_id, audience, min_value, max_value, unit, notes, region)
select id, 'general', 200, 350, 'mg', 'Voedingsaanvulling', 'NL' from public.actives where slug = 'magnesium'
on conflict do nothing;
insert into public.recommended_doses (active_id, audience, min_value, max_value, unit, notes, region)
select id, 'sports', 3, 5, 'g', 'Dagelijkse onderhoudsdosis', 'NL' from public.actives where slug = 'creatine'
on conflict do nothing;

insert into public.active_content (active_id, lang, audience, sections, sources)
select id, 'nl', 'general',
    '{"tldr": ["D3 is de standaard, D2 is plantaardig.", "Gebruikelijk 10–20 µg per dag."],
      "faq": [
        {"q": "D2 of D3?", "a": "D3 heeft meestal de voorkeur, D2 is plantaardig."},
        {"q": "Wanneer innemen?", "a": "Neem met een maaltijd met vet."},
        {"q": "Dagelijks veilig?", "a": "Blijf binnen de aanbevolen grenzen."}
      ]
    }'::jsonb,
    '[{"title": "EFSA Vitamin D", "url": "https://www.efsa.europa.eu/"}]'::jsonb
from public.actives where slug = 'vitamin-d'
on conflict do nothing;

insert into public.active_content (active_id, lang, audience, sections, sources)
select id, 'en', 'general',
    '{"tldr": ["D3 is standard, D2 is plant-based.", "Typical range 10–20 µg daily."],
      "faq": [
        {"q": "D2 or D3?", "a": "D3 is typically preferred; D2 is plant-based."},
        {"q": "When to take?", "a": "Take with a meal containing fat."},
        {"q": "Safe daily?", "a": "Stay within recommended ranges."}
      ]
    }'::jsonb,
    '[{"title": "NIH Vitamin D", "url": "https://ods.od.nih.gov/"}]'::jsonb
from public.actives where slug = 'vitamin-d'
on conflict do nothing;

insert into public.active_content (active_id, lang, audience, sections, sources)
select id, 'de', 'general',
    '{"tldr": ["D3 ist Standard, D2 ist pflanzlich.", "Üblich 10–20 µg täglich."],
      "faq": [
        {"q": "D2 oder D3?", "a": "D3 wird meist bevorzugt; D2 ist pflanzlich."},
        {"q": "Wann einnehmen?", "a": "Mit einer fetthaltigen Mahlzeit."},
        {"q": "Täglich sicher?", "a": "Im empfohlenen Bereich bleiben."}
      ]
    }'::jsonb,
    '[{"title": "BfR Vitamin D", "url": "https://www.bfr.bund.de/"}]'::jsonb
from public.actives where slug = 'vitamin-d'
on conflict do nothing;

insert into public.active_content (active_id, lang, audience, sections, sources)
select id, 'nl', 'general',
    '{"tldr": ["Bisglycinaat is mild, citraat werkt sneller.", "Dosering hangt af van voeding."],
      "faq": [
        {"q": "Citraat of bisglycinaat?", "a": "Bisglycinaat is mild, citraat kan sterker zijn."},
        {"q": "Beste tijdstip?", "a": "Met maaltijden of verdeeld."},
        {"q": "Combineren?", "a": "Ja, houd de totale dosis in de gaten."}
      ]
    }'::jsonb,
    '[]'::jsonb
from public.actives where slug = 'magnesium'
on conflict do nothing;

insert into public.active_content (active_id, lang, audience, sections, sources)
select id, 'en', 'general',
    '{"tldr": ["Bisglycinate is gentle, citrate can feel stronger.", "Dose depends on diet."],
      "faq": [
        {"q": "Citrate or bisglycinate?", "a": "Bisglycinate is gentle; citrate can be more noticeable."},
        {"q": "Best timing?", "a": "With meals or split across the day."},
        {"q": "Combine forms?", "a": "Yes, keep total intake in check."}
      ]
    }'::jsonb,
    '[]'::jsonb
from public.actives where slug = 'magnesium'
on conflict do nothing;

insert into public.active_content (active_id, lang, audience, sections, sources)
select id, 'de', 'general',
    '{"tldr": ["Bisglycinat ist sanft, Citrat wirkt stärker.", "Dosis abhängig von Ernährung."],
      "faq": [
        {"q": "Citrat oder Bisglycinat?", "a": "Bisglycinat ist sanft, Citrat wirkt stärker."},
        {"q": "Beste Einnahmezeit?", "a": "Mit Mahlzeiten oder verteilt."},
        {"q": "Kombinieren?", "a": "Ja, Gesamtmenge beachten."}
      ]
    }'::jsonb,
    '[]'::jsonb
from public.actives where slug = 'magnesium'
on conflict do nothing;

insert into public.active_content (active_id, lang, audience, sections, sources)
select id, 'nl', 'sports',
    '{"tldr": ["Monohydraat is het meest onderzocht.", "Typisch 3–5 g per dag."],
      "faq": [
        {"q": "Laadfase nodig?", "a": "Niet verplicht; dagelijks nemen werkt."},
        {"q": "Wanneer innemen?", "a": "Elke dag op een vast moment."},
        {"q": "Waterretentie?", "a": "Sommigen merken een kleine toename."}
      ]
    }'::jsonb,
    '[]'::jsonb
from public.actives where slug = 'creatine'
on conflict do nothing;

insert into public.active_content (active_id, lang, audience, sections, sources)
select id, 'en', 'sports',
    '{"tldr": ["Monohydrate is the most studied.", "Typical 3–5 g per day."],
      "faq": [
        {"q": "Is loading required?", "a": "Not required; steady daily intake works."},
        {"q": "When to take?", "a": "Any consistent time daily."},
        {"q": "Water retention?", "a": "Some notice a small increase."}
      ]
    }'::jsonb,
    '[]'::jsonb
from public.actives where slug = 'creatine'
on conflict do nothing;

insert into public.active_content (active_id, lang, audience, sections, sources)
select id, 'de', 'sports',
    '{"tldr": ["Monohydrat ist am besten untersucht.", "Typisch 3–5 g pro Tag."],
      "faq": [
        {"q": "Ladephase nötig?", "a": "Nicht nötig; täglich einnehmen reicht."},
        {"q": "Wann einnehmen?", "a": "Täglich zur gleichen Zeit."},
        {"q": "Wassereinlagerung?", "a": "Manche merken eine kleine Zunahme."}
      ]
    }'::jsonb,
    '[]'::jsonb
from public.actives where slug = 'creatine'
on conflict do nothing;
