import os

ext = ['.png', '.jpg', '.webp']
images = [x for x in os.listdir() if os.path.isfile(x) and os.path.splitext(x)[-1] in ext]

print(images)