

USE healthinfo;

CREATE TABLE dietPlans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    age_group VARCHAR(50) NOT NULL,
    gender VARCHAR(50) NOT NULL,
    activity_level VARCHAR(50) NOT NULL,
    goal TEXT NOT NULL,
    diet_plan TEXT NOT NULL
);


drop table dietplans;
INSERT INTO dietPlans (age_group, gender, activity_level, goal, diet_plan) VALUES
('18-25', 'Male', 'Low', 'Weight Loss', 'Breakfast: Oats with milk. Lunch: Grilled chicken with rice. Dinner: Baked fish with vegetables.'),
('18-25', 'Male', 'High', 'Muscle Gain', 'Breakfast: Eggs with toast and avocado. Lunch: Chicken wrap with salad. Dinner: Steak with sweet potatoes.'),
('18-25', 'Female', 'Low', 'Weight Loss', 'Breakfast: Smoothie with fruits. Lunch: Quinoa salad. Dinner: Grilled tofu with stir-fry vegetables.'),
('18-25', 'Female', 'High', 'Muscle Gain', 'Breakfast: Peanut butter toast. Lunch: Turkey sandwich. Dinner: Salmon with broccoli.'),
('26-30', 'Male', 'Low', 'Weight Loss', 'Breakfast: Porridge with honey. Lunch: Lentil soup. Dinner: Grilled chicken breast with spinach.'),
('26-30', 'Male', 'High', 'Muscle Gain', 'Breakfast: Protein shake. Lunch: Beef stew with bread. Dinner: Roasted turkey with beans.'),
('26-30', 'Female', 'Low', 'Weight Loss', 'Breakfast: Yogurt with granola. Lunch: Veggie wrap. Dinner: Baked salmon with asparagus.'),
('26-30', 'Female', 'High', 'Muscle Gain', 'Breakfast: Scrambled eggs with toast. Lunch: Chicken pasta. Dinner: Grilled prawns with vegetables.'),
('31-40', 'Male', 'Low', 'Weight Loss', 'Breakfast: Whole wheat toast with avocado. Lunch: Chicken salad. Dinner: Steamed vegetables with fish.'),
('31-40', 'Male', 'High', 'Muscle Gain', 'Breakfast: Scrambled eggs with spinach. Lunch: Grilled chicken with quinoa. Dinner: Roast beef with sweet potatoes.'),
('31-40', 'Female', 'Low', 'Weight Loss', 'Breakfast: Greek yogurt with honey. Lunch: Veggie wrap with hummus. Dinner: Grilled salmon with broccoli.'),
('31-40', 'Female', 'High', 'Muscle Gain', 'Breakfast: Oats with peanut butter. Lunch: Chicken stir-fry. Dinner: Grilled turkey with vegetables.'),
('41-50', 'Male', 'Low', 'Weight Loss', 'Breakfast: Oatmeal with berries. Lunch: Tuna salad. Dinner: Baked chicken with vegetables.'),
('41-50', 'Male', 'High', 'Muscle Gain', 'Breakfast: Cottage cheese with fruit. Lunch: Turkey wrap with veggies. Dinner: Grilled fish with green beans.'),
('41-50', 'Female', 'Low', 'Weight Loss', 'Breakfast: Smoothie with spinach. Lunch: Quinoa salad with chickpeas. Dinner: Baked salmon with steamed vegetables.'),
('41-50', 'Female', 'High', 'Muscle Gain', 'Breakfast: Scrambled eggs with spinach. Lunch: Chicken and vegetable soup. Dinner: Grilled shrimp with brown rice.'),
('51-60', 'Male', 'Low', 'Weight Loss', 'Breakfast: Scrambled eggs with mushrooms. Lunch: Chicken stir-fry. Dinner: Roasted vegetables with fish.'),
('51-60', 'Male', 'High', 'Muscle Gain', 'Breakfast: Protein pancakes. Lunch: Grilled turkey sandwich. Dinner: Beef and vegetable stew.'),
('51-60', 'Female', 'Low', 'Weight Loss', 'Breakfast: Yogurt with berries. Lunch: Spinach salad with nuts. Dinner: Baked chicken with quinoa.'),
('51-60', 'Female', 'High', 'Muscle Gain', 'Breakfast: Chia seed pudding. Lunch: Grilled chicken wrap. Dinner: Salmon with brown rice.'),
('60+', 'Male', 'Low', 'Weight Loss', 'Breakfast: Oatmeal with honey. Lunch: Grilled chicken with vegetables. Dinner: Steamed fish with quinoa.'),
('60+', 'Male', 'High', 'Muscle Gain', 'Breakfast: Scrambled eggs with spinach. Lunch: Chicken stir-fry. Dinner: Grilled salmon with vegetables.'),
('60+', 'Female', 'Low', 'Weight Loss', 'Breakfast: Smoothie with spinach and berries. Lunch: Quinoa salad. Dinner: Baked chicken with vegetables.'),
('60+', 'Female', 'High', 'Muscle Gain', 'Breakfast: Greek yogurt with nuts. Lunch: Turkey sandwich. Dinner: Grilled shrimp with brown rice.');
