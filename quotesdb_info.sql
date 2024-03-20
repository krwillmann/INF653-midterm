CREATE TABLE authors (
    id SERIAL PRIMARY KEY,
    author VARCHAR(50) NOT NULL
);

-- Create the categories table
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    category VARCHAR(20) NOT NULL
);

CREATE TABLE quotes (
    id SERIAL PRIMARY KEY,
    quote TEXT NOT NULL,
    author_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (author_id) REFERENCES authors(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO authors (author) VALUES
('Maya Angelou'),
('Albert Einstein'),
('Oprah Winfrey'),
('Nelson Mandela'),
('Steve Jobs');

INSERT INTO categories (category) VALUES
('Inspirational'),
('Motivational'),
('Love'),
('Wisdom'),
('Success');

INSERT INTO quotes (quote, author_id, category_id) VALUES
-- Maya Angelou
('Ive learned that people will forget what you said, people will forget what you did, but people will never forget how you made them feel.',1,1),
('We may encounter many defeats but we must not be defeated.',1,2),
('Love recognizes no barriers. It jumps hurdles, leaps fences, penetrates walls to arrive at its destination full of hope.',1,3),
('Try to be a rainbow in someones cloud.',1,4),
('Success is liking yourself, liking what you do, and liking how you do it.',1,5),

-- Albert Einstein
('Imagination is more important than knowledge. Knowledge is limited. Imagination encircles the world.',2,1),
('Life is like riding a bicycle. To keep your balance, you must keep moving.',2,2),
('Gravitation is not responsible for people falling in love.',2,3),
('Anyone who has never made a mistake has never tried anything new.',2,4),
('Strive not to be a success, but rather to be of value.',2,5),

-- Oprah Winfrey
('The biggest adventure you can take is to live the life of your dreams.',3,1),
('Turn your wounds into wisdom.',3,2),
('The more you praise and celebrate your life, the more there is in life to celebrate.',3,3),
('Real integrity is doing the right thing, knowing that nobodys going to know whether you did it or not.',3,4),
('Failure is another stepping stone to greatness.',3,5),

-- Nelson Mandela
('The greatest glory in living lies not in never falling, but in rising every time we fall.',4,1),
('It always seems impossible until its done.',4,2),
('No one is born hating another person because of the color of his skin, or his background, or his religion. People must learn to hate, and if they can learn to hate, they can be taught to love.',4,3),
('Education is the most powerful weapon which you can use to change the world.',4,4),
('I never lose. I either win or learn.',4,5),

-- Steve Jobs
('Your work is going to fill a large part of your life, and the only way to be truly satisfied is to do what you believe is great work. And the only way to do great work is to love what you do.',5,1),
('The people who are crazy enough to think they can change the world are the ones who do.',5,2),
('Youve got to find what you love. And that is as true for your work as it is for your lovers.',5,3),
('Remembering that Ill be dead soon is the most important tool Ive ever encountered to help me make the big choices in life.',5,4),
('Innovation distinguishes between a leader and a follower.',5,5);
