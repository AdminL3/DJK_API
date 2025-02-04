import shutil
import os
shutil.make_archive("djk_api", 'zip', "DJK_API")
os.startfile(os.path.abspath("djk_api.zip"))