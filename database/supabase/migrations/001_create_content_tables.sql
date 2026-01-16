create table if not exists public.actives (
    id bigserial primary key,
    slug text not null unique,
    default_unit text not null,
    category text null,
    created_at timestamptz not null default now(),
    updated_at timestamptz not null default now()
);

create table if not exists public.active_translations (
    id bigserial primary key,
    active_id bigint references public.actives(id) on delete cascade,
    lang text not null check (lang in ('nl', 'en', 'de')),
    name text not null,
    description_short text null,
    description_long text null,
    meta_title text null,
    meta_description text null,
    created_at timestamptz not null default now(),
    updated_at timestamptz not null default now(),
    unique (active_id, lang)
);

create table if not exists public.forms (
    id bigserial primary key,
    slug text not null unique,
    created_at timestamptz not null default now(),
    updated_at timestamptz not null default now()
);

create table if not exists public.form_translations (
    id bigserial primary key,
    form_id bigint references public.forms(id) on delete cascade,
    lang text not null check (lang in ('nl', 'en', 'de')),
    name text not null,
    description text null,
    created_at timestamptz not null default now(),
    updated_at timestamptz not null default now(),
    unique (form_id, lang)
);

create table if not exists public.goals (
    id bigserial primary key,
    slug text not null unique,
    created_at timestamptz not null default now(),
    updated_at timestamptz not null default now()
);

create table if not exists public.goal_translations (
    id bigserial primary key,
    goal_id bigint references public.goals(id) on delete cascade,
    lang text not null check (lang in ('nl', 'en', 'de')),
    name text not null,
    description text null,
    created_at timestamptz not null default now(),
    updated_at timestamptz not null default now(),
    unique (goal_id, lang)
);

create table if not exists public.active_forms (
    active_id bigint references public.actives(id) on delete cascade,
    form_id bigint references public.forms(id) on delete cascade,
    primary key (active_id, form_id)
);

create table if not exists public.active_goals (
    active_id bigint references public.actives(id) on delete cascade,
    goal_id bigint references public.goals(id) on delete cascade,
    primary key (active_id, goal_id)
);

create table if not exists public.active_content (
    id bigserial primary key,
    active_id bigint references public.actives(id) on delete cascade,
    lang text not null check (lang in ('nl', 'en', 'de')),
    audience text not null check (audience in ('general', 'elderly', 'sports')),
    sections jsonb not null default '{}'::jsonb,
    sources jsonb not null default '[]'::jsonb,
    updated_at timestamptz not null default now(),
    unique (active_id, lang, audience)
);

create table if not exists public.recommended_doses (
    id bigserial primary key,
    active_id bigint references public.actives(id) on delete cascade,
    audience text not null check (audience in ('general', 'elderly', 'sports')),
    min_value numeric not null,
    max_value numeric not null,
    unit text not null,
    notes text null,
    region text null,
    created_at timestamptz not null default now(),
    updated_at timestamptz not null default now()
);

create table if not exists public.unit_conversions (
    id bigserial primary key,
    from_unit text not null,
    to_unit text not null,
    multiplier numeric not null,
    active_slug text null,
    is_safe boolean not null default false,
    notes text null,
    created_at timestamptz not null default now(),
    updated_at timestamptz not null default now()
);

create table if not exists public.sponsored_entries (
    id bigserial primary key,
    active_slug text not null,
    lang text null,
    country text null,
    title text not null,
    description text null,
    target_url text not null,
    tracking_params jsonb null,
    priority int not null default 0,
    bid_cpc numeric null,
    starts_at timestamptz null,
    ends_at timestamptz null,
    is_enabled boolean not null default false,
    created_at timestamptz not null default now(),
    updated_at timestamptz not null default now()
);
