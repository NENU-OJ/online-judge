from PIL import Image
import os

imgs = filter(lambda x: True if x.endswith('.png') else False, os.listdir())
print("-----")
for img in imgs:
    name = img[:-4]
    os.mkdir(name)
    im = Image.open(img)
    lst = [250, 150, 75, 50]
    for sub in range(4):
        of = "%s/%d.png" % (name, sub + 1)
        im.resize((lst[sub], lst[sub]),Image.ANTIALIAS).save(of)
